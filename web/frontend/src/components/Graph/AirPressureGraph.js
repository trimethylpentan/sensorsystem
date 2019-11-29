import * as React from "react";
import type {Point} from "../../types/Point";
import {Line} from "react-chartjs-2";
import DataFormatter from "../../helpers/DateFormatter";
import GraphStyle from "../../helpers/GraphStyle";

type Props = {
    points: Point[]
}

export default class AirPressureGraph extends React.Component<Props, {}> {
    render() {
        const { points }      = this.props;
        const labels          = points.map((point: Point) => DataFormatter.format(point.timestamp));
        const dataAirPressure = points.map((point: Point) => point.airPressure);

        const style = GraphStyle.style;

        const data = {
            labels,
            datasets: [
                {
                    label: 'Luftdruck (hPa)',
                    borderColor: 'rgb(92,205,69)',
                    backgroundColor: 'rgba(92,205,69,.07)',
                    ...style,
                    data: dataAirPressure,
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
