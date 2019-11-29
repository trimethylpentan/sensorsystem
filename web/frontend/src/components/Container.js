import React from 'react';
import Button from "./Button";
import Table from "./Table";
import Settings from "./Settings";
import type {SettingsType} from "../types/SettingsType";
import type {Point} from "../types/Point";
import ApiClient from "../helpers/ApiClient";
import MeasurementSocket from "../helpers/MeasurementSocket";
import PointOrganizer from "../helpers/PointOrganizer";
import GraphContainer from "./Graph/GraphContainer";
import DateFormatter from "../helpers/DateFormatter";

type State = {
    activeComponent: string,
    errorMessage: ?string,
    settings: SettingsType,
    points: Point[],
};

class Container extends React.Component<{}, State> {
    state = {
        activeComponent: 'graph',
        errorMessage: null,
        points: [],
        settings: {
            // host: '172.16.64.73',
            // host: '172.16.112.29',
            host: window.location.hostname,
            maxPoints: 30,
            random: false,
            dateRange: {
                enabled: false,
                from: new Date(new Date().setHours(0,0,0,0)),
                to: new Date(new Date().setHours(24,0,0,0)),
            }
        },
    };

    constructor(props) {
        super(props);

        this.apiClient = new ApiClient();
        this.webSocket = new MeasurementSocket();
        this.initSocket()
    }

    initSocket() {
        this.webSocket.onOpen(() => console.log('Opened Socket-Connection'));
        this.webSocket.onClose(() => console.log('Closed Socket-Connection'));
        this.webSocket.onMessage((msg) => this.mergeSocketPoints(msg.points));
        this.webSocket.onError((errorEvent: MessageEvent) => console.log(errorEvent));
    }

    buttonClick(event, name) {
        this.setState({
            activeComponent: name
        })
    }

    refreshSettings(rawSettings: SettingsType) {
        console.log(rawSettings);

        let newSettings = {...rawSettings};
        const from: string = rawSettings.dateRange.from;
        const to: string   = rawSettings.dateRange.to;
        newSettings.dateRange = {
            enabled: rawSettings.dateRange.enabled,
            from: new Date(DateFormatter.germanToIso(from)),
            to: new Date(DateFormatter.germanToIso(to)),
        };

        this.webSocket.close();
        this.webSocket.open(newSettings.host);

        this.setState({
            settings: newSettings,
            errorMessage: null,
        }, this.refreshPoints.bind(this));
    }

    componentDidMount(): void {
        this.webSocket.open(this.state.settings.host);

        if (this.state.errorMessage === null) {
            this.refreshPoints();
        }
    }

    mergeSocketPoints(newPoints: Point[]) {
        const oldPoints = this.state.points;
        const rangeSettings = this.state.settings.dateRange;

        let merged = [...oldPoints, ...newPoints];

        if (rangeSettings.enabled) {
            PointOrganizer.filterDateRange(merged, rangeSettings.from, rangeSettings.to);
        }

        merged = PointOrganizer.sortByDate(merged);

        const maxPoints = this.state.settings.maxPoints;
        if (merged.length > maxPoints) {
            merged = merged.slice(merged.length - maxPoints);
        }

        this.setState({
            points: merged,
        })
    }

    refreshPoints() {
        const { maxPoints, host, random} = this.state.settings;
        const dateRangeSettings = this.state.settings.dateRange;

        if (dateRangeSettings.enabled) {
            this.apiClient.requestRange(host, dateRangeSettings.from, dateRangeSettings.to).then(
                (response) => {
                    const sortedPoints = PointOrganizer.sortByDate(
                        response.points.slice(response.points.length - this.state.settings.maxPoints),
                        dateRangeSettings.from,
                        dateRangeSettings.to
                    );

                    this.setState({
                        points: sortedPoints,
                        errorMessage: null,
                    })
                },
                (error) => {
                    console.log(error);
                    this.setState({
                        activeComponent: 'error',
                        errorMessage: 'Verbindung zur API nicht möglich',
                    })
                }
            );

            return;
        }

        this.apiClient.requestLatest(
            maxPoints,
            host,
            random,
        ).then(
            (response) => {
                const rawPoints    = response.points;
                const sortedPoints = PointOrganizer.sortByDate(rawPoints);

                this.setState({
                    points: sortedPoints,
                    errorMessage: null,
                })
            },
            (error) => {
                console.log(error);
                this.setState({
                    activeComponent: 'error',
                    errorMessage: 'Verbindung zur API nicht möglich',
                })
            }
        )
    }

    renderContent() {
        switch (this.state.activeComponent) {
            case 'graph':
                return <GraphContainer points={this.state.points}/>;
            case 'table':
                return <Table points={this.state.points}/>;
            case 'settings':
                return <Settings settings={this.state.settings} accept={this.refreshSettings.bind(this)}/>;
            case 'error':
                return <span className={'error'}>Fehler: {this.state.errorMessage}</span>
        }

        return null;
    }

    render() {
        return (
            <div className="container">
                <div className={'head'}>
                    <Button
                        name={'graph'}
                        active={this.state.activeComponent === 'graph'}
                        click={this.buttonClick.bind(this)}
                    >Graph</Button>
                    <Button
                        name={'table'}
                        active={this.state.activeComponent === 'table'}
                        click={this.buttonClick.bind(this)}
                    >Tabelle</Button>
                    <Button
                        name={'settings'}
                        active={this.state.activeComponent === 'settings'}
                        click={this.buttonClick.bind(this)}
                    >Einstellungen</Button>
                </div>
                <hr/>
                <div className={'content'}>
                    {this.renderContent()}
                </div>
            </div>
        );
    }
}

export default Container;
