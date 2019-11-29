// @flow

import React from 'react';

type Props = {
    name: string,
    label: string,
    value: string,
    classes: string,
    update: Function,
};

/**
 * @deprecated
 * Unused
 */
class TextInput extends React.Component<Props> {
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
                />
            </div>
        );
    }
}

export default TextInput;
