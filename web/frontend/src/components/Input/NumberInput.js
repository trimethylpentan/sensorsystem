// @flow

import React from 'react';
import MathTools from "../../helpers/MathTools";

type Props = {
    name: string,
    label: string,
    value: string,
    classes: string,
    update: Function<string, any>,
    min: number,
    max: number,
};

class NumberInput extends React.Component<Props> {
    updateValue(event: SyntheticEvent) {
        const { min, max } = this.props;
        const newValue     = parseInt(event.currentTarget.value || 0, 10);
        const clamped      = MathTools.clamp(newValue, min, max);

        const update = this.props.update;
        update(this.props.name, clamped);
    }

    keyPressed(event: KeyboardEvent) {
        const {onEnter} = this.props;

        if (onEnter && event.key === 'Enter') {
            onEnter()
        }
    }

    render() {
        const {name, label, value, classes, min, max} = this.props;
        const labelClass = classes === null ? '' : 'font-italic';

        return (
            <div className="input">
                <label htmlFor={name} className={labelClass}>{label}</label>
                <input
                    type={'number'}
                    id={name}
                    name={name}
                    min={min}
                    max={max}
                    value={value}
                    onChange={this.updateValue.bind(this)}
                    className={`input-native${classes === null ? '' : ` ${classes}`}`}
                    onKeyPress={this.keyPressed.bind(this)}
                />
            </div>
        );
    }
}

export default NumberInput;
