import React from 'react';
import type {Point} from "../types/Point";
import type {Color} from "../types/Color";
import DateFormatter from "../helpers/DateFormatter";

type Props = {
    points: Point[],
};

type State = {};

class Table extends React.Component<Props, State> {
    formatColor(color: Color) {
        const r = color.r.toString(16).padStart(2, '0');
        const g = color.g.toString(16).padStart(2, '0');
        const b = color.b.toString(16).padStart(2, '0');

        return `#${r}${g}${b}`;
    }

    render() {
        return (
            <div className="measurement-table">
                <table>
                    <thead>
                    <tr>
                        <th className={'center-head'}>Zeitpunkt</th>
                        <th className={'center-head'}>Temperatur</th>
                        <th className={'center-head'}>Luftfeuchtigkeit</th>
                        <th className={'center-head'}>Luftdruck</th>
                        <th className={'center-head'}>Farbe</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.props.points.map((point: Point) => {
                        const colorHex = this.formatColor(point.color);

                        return (
                            <tr key={point.timestamp}>
                                <td>{DateFormatter.format(point.timestamp)}</td>
                                <td>{point.temperature.toFixed(2)} °C</td>
                                <td>{Math.round(point.humidity)} %</td>
                                <td>{point.airPressure.toFixed(2)} hPa</td>
                                <td style={{ color: colorHex }}>{colorHex}</td>
                            </tr>
                        );
                    })}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default Table;
