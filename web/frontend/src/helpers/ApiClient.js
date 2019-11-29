// @flow

import type {ApiResponse} from "../types/ApiResponse";

class ApiClient {
    requestLatest(amount: number = 10, host: string = '172.16.112.73', random: boolean = false): Promise<ApiResponse> {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            const url = `http://${host}/api${random ? '/random': ''}?latest=${amount}`;

            xhr.open('GET', url);
            xhr.send();

            xhr.onload  = (event) => resolve(this._parseResponse(event));
            xhr.onerror = (event: ProgressEvent) => reject(event);
        });
    }

    requestRange(host: string, from: Date, to: Date): Promise<ApiResponse> {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            const url = `http://${host}/api?from=${from.toISOString()}&to=${to.toISOString()}`;

            xhr.open('GET', url);
            xhr.send();

            xhr.onload  = (event) => resolve(this._parseResponse(event));
            xhr.onerror = (event: ProgressEvent) => reject(event);
        });
    }

    _parseResponse(event: ProgressEvent): ApiResponse {
        const target: XMLHttpRequest = event.target;
        const response = target.responseText;
        return JSON.parse(response);
    }
}

export default ApiClient;
