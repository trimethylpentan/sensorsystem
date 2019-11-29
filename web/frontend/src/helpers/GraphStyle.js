type GraphStyleType = {
    fill: boolean,
    lineTension: number,
    borderCapStyle: string,
    borderDash: string[],
    borderDashOffset: number,
    borderJoinStyle: string,
    pointBorderColor: string,
    pointBackgroundColor: string,
    pointBorderWidth: number,
    pointHoverRadius: number,
    pointHoverBackgroundColor: string,
    pointHoverBorderColor: string,
    pointHoverBorderWidth: number,
    pointRadius: number,
    pointHitRadius: number,
}

export default class GraphStyle {
    static style: GraphStyleType = {
        fill: true,
        lineTension: .2,
        borderCapStyle: 'butt',
        borderDash: [],
        borderDashOffset: 0.0,
        borderJoinStyle: 'miter',
        pointBorderColor: 'rgb(230, 230, 230)',
        pointBackgroundColor: 'rgba(42,42,42,1)',
        pointBorderWidth: 3,
        pointHoverRadius: 5,
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgb(230, 230, 230)',
        pointHoverBorderWidth: 1.5,
        pointRadius: 1.5,
        pointHitRadius: 10,
    }
}
