// @flow

class MeasurementSocket {

    isOpen = false;

    open(host: string = '172.16.112.73') {
        this.socket = new WebSocket(`ws://${host}/ws/`);

        this.socket.onopen    = this._onOpenHandler;
        this.socket.onclose   = this._onCloseHandler;
        this.socket.onerror   = this._onErrorHandler;
        this.socket.onmessage = (data: MessageEvent) => {this._onMessageHandler(JSON.parse(data.data))};

        this.isOpen = true;
    }

    close() {
        this.socket.close();
        this.isOpen = false;
    }

    onOpen(handler: Function<Event>) {
        this._onOpenHandler = handler;
    }

    onClose(handler: Function<CloseEvent>) {
        this._onCloseHandler = handler;
    }

    onError(handler: Function<Event>) {
        this._onErrorHandler = handler;
        this.isOpen = false;
    }

    onMessage(handler: Function<MessageEvent>) {
        this._onMessageHandler = handler;
    }

    // onOpen:    Function<Event> = (event) => {};
    // onClose:   Function<CloseEvent> = (event) => {};
    // onError:   Function<Event> = (event) => {};
    // onMessage: Function<MessageEvent> = (event) => {};
}

export default MeasurementSocket;
