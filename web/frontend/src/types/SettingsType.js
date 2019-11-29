export type SettingsType = {
    host: string,
    maxPoints: number,
    random: boolean,
    dateRange: {
        enabled: boolean,
        from: Date,
        to: Date,
    },
}
