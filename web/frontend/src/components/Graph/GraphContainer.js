// @flow
import React from 'react';
import type {Point} from '../../types/Point';
import Button from '../Button';
import MainGraph from './MainGraph';
import TemperatureGraph from './TemperatureGraph';
import HumidityGraph from './HumidityGraph';
import AirPressureGraph from './AirPressureGraph';

type Props = {
    points: Point[]
}

type State = {
    selectedGraph: string;
}

export default class GraphContainer extends React.Component<Props, State> {
    state = {
        selectedGraph: 'all',
    };

    buttonClick(event: SyntheticMouseEvent, name: string) {
        this.setState({
            selectedGraph: name,
        })
    }

    render() {
        const graphTypes = {
            'all': 'Alle',
            'temperature': 'Temperatur',
            'humidity': 'Luftfeuchtigkeit',
            'airPressure': 'Luftdruck',
        };

        return (
            <>
                <div className={'head'} id={'graph-head'}>
                    {Object.keys(graphTypes).map((key) => {
                        const display = graphTypes[key];

                        return (
                            <Button
                                name={key}
                                active={this.state.selectedGraph === key}
                                click={this.buttonClick.bind(this)}
                                key={key}
                            >{display}</Button>
                        );
                    })}
                </div>
                <hr/>
                {this.renderGraph()}
            </>
        );
    }

    renderGraph() {
        switch (this.state.selectedGraph) {
            case 'all':
                return <MainGraph points={this.props.points}/>;
            case 'temperature':
                return <TemperatureGraph points={this.props.points}/>;
            case 'humidity':
                return <HumidityGraph points={this.props.points}/>;
            case 'airPressure':
                return <AirPressureGraph points={this.props.points}/>;
        }

        return null;
    }
}
