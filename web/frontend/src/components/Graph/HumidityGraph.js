import * as React from 'react';
import type {Point} from '../../types/Point';
import {Line} from 'react-chartjs-2';
import DataFormatter from '../../helpers/DateFormatter';
import GraphStyle from '../../helpers/GraphStyle';

type Props = {
    points: Point[]
}

export default class HumidityGraph extends React.Component<Props> {
    render() {
        const { points }   = this.props;
        const labels       = points.map((point: Point) => DataFormatter.format(point.timestamp));
        const dataHumidity = points.map((point: Point) => point.humidity);

        const style = GraphStyle.style;

        const data = {
            labels,
            datasets: [
                {
                    label: 'Luftfeuchtigkeit (%)',
                    borderColor: 'rgb(0,136,254)',
                    backgroundColor: 'rgba(0,136,254,.07)',
                    ...style,
                    data: dataHumidity,
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
