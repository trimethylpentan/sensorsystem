import React from 'react';
import {Line} from 'react-chartjs-2';
import type {Point} from '../../types/Point';
import DataFormatter from '../../helpers/DateFormatter';
import GraphStyle from '../../helpers/GraphStyle';

type Props = {
    points: Point[],
}

export default class MainGraph extends React.Component<Props> {
    render() {
        const { points }      = this.props;
        const labels          = points.map((point: Point) => DataFormatter.format(point.timestamp));
        const dataTemperature = points.map((point: Point) => point.temperature);
        const dataHumidity    = points.map((point: Point) => point.humidity);
        const dataPressure    = points.map((point: Point) => point.airPressure * .1);

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
                {
                    label: 'Luftfeuchtigkeit (%)',
                    borderColor: 'rgb(0,136,254)',
                    backgroundColor: 'rgba(0,136,254,.07)',
                    ...style,
                    data: dataHumidity,
                },
                {
                    label: 'Luftdruck (10 hPa)',
                    borderColor: 'rgb(92,205,69)',
                    backgroundColor: 'rgba(92,205,69,.07)',
                    ...style,
                    data: dataPressure,
                }
            ]
        };

        return (
            <Line
                data={data}
                // height={500}
                // options={{ maintainAspectRatio: false }}
            />
        )
    }
}