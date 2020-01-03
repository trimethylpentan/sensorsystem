// @flow

import React from 'react';
import ApiClient from "../helpers/ApiClient";
import MeasurementSocket from "../helpers/MeasurementSocket";
import type {Point} from "../types/Point";
import type {Color} from "../types/Color";

/**
 * @deprecated
 * Debug only, do not use
 */
class DebugTest extends React.Component {

    state = {
        isSync: false,
        points: {},
    };

    constructor(props) {
        super(props);

        this.apiClient = new ApiClient();
        this.socket    = new MeasurementSocket();
    }

    componentDidMount(): void {
        this.socket.onMessage(this.updatePoints.bind(this));
        this.socket.onOpen((event) => console.log('Socket Connected'));
        this.socket.onClose((event) => console.log('Socket Disconnected'));
    }

    updatePoints(data: { points: Point[] }) {
        this.setState({
            points: {...this.state.points, ...data.points},
        });

        // this.socket.close();
    }

    request() {
        const request = this.apiClient.requestLatest();
        request.then((data) => this.setState(data), (error) => (console.error(error)));
    }

    static colorToHex(color: Color) {
        return `${color.r.toString(16)}${color.g.toString(16)}${color.b.toString(16)}`;
    }

    changeSync() {
        this.setState({isSync: !this.socket.isOpen});

        if (this.socket.isOpen) {
            this.socket.close();
            return;
        }

        this.socket.open();
    }

    render() {
        console.log(this.state.points);

        return (
            <div className="debug">
                <p>{this.state.value}</p>
                <button onClick={this.request.bind(this)}>Request API</button>
                <button onClick={() => {this.changeSync()}}>
                    {this.state.isSync ? 'Stop' : 'Start'} Sync
                </button>
                <div>
                    {Object.keys(this.state.points).map((key) => {
                        const point = this.state.points[key];
                        const {timestamp, temperature, humidity, airPressure, color} = point;
                        const hexColor = `#${DebugTest.colorToHex(color)}`;

                        return (
                            <div key={key}>
                                <span>Zeit: {timestamp}</span><br/>
                                <span>Temperatur: {temperature}</span><br/>
                                <span>Luftfeuchte: {humidity}</span><br/>
                                <span>Luftdruck: {airPressure}</span><br/>
                                <span style={{backgroundColor: hexColor}}>Color: <span>{hexColor}</span></span><br/>
                                <br/>
                                <hr/>
                            </div>
                        )
                    })}
                </div>
            </div>
        );
    }
}

export default DebugTest;
