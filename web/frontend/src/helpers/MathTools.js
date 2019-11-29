export default class MathTools {
    /**
     * Clamping value between min and max
     *
     * @param value
     * @param min
     * @param max
     * @returns {number}
     */
    static clamp(value: number, min: number, max: number): number {
        return Math.min(Math.max(min, value), max)
    }
}
