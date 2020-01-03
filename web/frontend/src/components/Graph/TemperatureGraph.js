import * as React from 'react';
import type {Point} from '../../types/Point';
import {Line} from 'react-chartjs-2';
import DataFormatter from '../../helpers/DateFormatter';
import GraphStyle from '../../helpers/GraphStyle';

type Props = {
    points: Point[]
}

export default class TemperatureGraph extends React.Component<Props> {
    render() {
        const { points }     = this.props;
        const labels          = points.map((point: Point) => DataFormatter.format(point.timestamp));
        const dataTemperature = points.map((point: Point) => point.temperature);

        const style = GraphStyle.style;

        const data = {
            labels,
            datasets: [
                {
                    label: 'Temperatur (°C)',
                    borderColor: 'rgb(225,57,27)',
                    backgroundColor: 'rgba(225,57,27,.07)',
                    ...style,
                    data: dataTemperature,
                },
            ]
        };

        return (
            <Line
                data={data}
            />
        );
    }
}
