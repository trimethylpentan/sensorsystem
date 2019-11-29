import type {Point} from "./Point";

export type ApiResponse = {
    statusCode: ?number,
    points: Point[],
}
