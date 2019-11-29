// @flow

import React from 'react';

type Props = {
    active: boolean,
    name: string,
    click: Function<Event, string>
};

class Button extends React.Component<Props> {
    render() {
        return (
            <div
                className={`button${this.props.active ? ' active' : ''}`}
                onClick={(event) => this.props.click(event, this.props.name)}
                id={this.props.name}
            >
                <span>{this.props.children}</span>
            </div>
        );
    }
}

export default Button;
