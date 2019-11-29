export default class DataFormatter {
    static format(dateIso: string): string {
        const date = new Date(dateIso);

        return `${date.toLocaleDateString('de-DE')} ${date.toLocaleTimeString('de-DE')}`
    }

    // Deutsches Datumsformat in Starndardisiertes Format umbasteln
    static germanToIso(dateGerman: string): string {
        console.log(dateGerman);
        const pattern = /(\d{1,2})\.(\d{1,2})\.(\d{4})\s(\d{1,2}):(\d{1,2}):(\d{1,2})/;
        return dateGerman.replace(pattern, '$3-$2-$1 $4:$5:$6');
    }
}
