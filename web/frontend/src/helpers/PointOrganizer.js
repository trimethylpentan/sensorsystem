import type {Point} from "../types/Point";

export default class PointOrganizer {
    static sortByDate(points: Point[]): Point[] {
        return points.sort((pointA: Point, pointB: Point) => {
            const dateA = new Date(pointA.timestamp);
            const dateB = new Date(pointB.timestamp);

            if (dateA.getTime() < dateB.getTime()) {
                return -1
            }

            if (dateA.getTime() > dateB.getTime()) {
                return 1
            }

            return 0;
        })
    }

    static filterDateRange(points: Point[], from: Date, to: Date): Point[] {
        return points.filter((point) => {
            let pointTime = new Date(point.timestamp).getTime();

            return pointTime > from.getTime() && pointTime < to.getTime()
        });
    }
}
