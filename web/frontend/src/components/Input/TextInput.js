// @flow

import React from 'react';

type Props = {
    name: string,
    label: string,
    value: string,
    classes: string,
    update: Function,
};

class TextInput extends React.Component<Props> {
    keyPressed(event: KeyboardEvent) {
        const {onEnter} = this.props;

        if (onEnter && event.key === 'Enter') {
            onEnter()
        }
    }

    render() {
        const {name, label, value, classes, update} = this.props;
        const labelClass = classes === null ? '' : 'font-italic';

        return (
            <div className="input">
                <label htmlFor={name} className={labelClass}>{label}</label>
                <input
                    type={'text'}
                    id={name}
                    name={name}
                    value={value}
                    onChange={update}
                    className={`input-native${classes === null ? '' : ` ${classes}`}`}
                    onKeyPress={this.keyPressed.bind(this)}
                />
            </div>
        );
    }
}

export default TextInput;
