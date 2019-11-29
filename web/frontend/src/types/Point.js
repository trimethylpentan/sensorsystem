export type Point = {
    timestamp: string,
    color: {
        r: number,
        g: number,
        b: number,
    },
    humidity: number,
    temperature: number,
    airPressure: number,
}
