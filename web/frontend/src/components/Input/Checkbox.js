import * as React from "react";
import {SyntheticEvent} from "react";
import NumberInput from "./NumberInput";

type Props = {
    name: string,
    label: string,
    value: boolean,
    classes: string,
    disabled: boolean,
    update: Function<string, any>,
    onEnter: ?Function,
};

export default class CheckBox extends React.Component<Props, {}> {
    updateValue(event: SyntheticEvent) {
        const input  = event.currentTarget;
        const update = this.props.update;

        update(this.props.name, input.checked);
    }

    keyPressed(event: KeyboardEvent) {
        const {onEnter} = this.props;

        if (onEnter && event.key === 'Enter') {
            onEnter()
        }
    }

    render() {
        const {name, label, value, classes} = this.props;
        const labelClass = classes === null ? '' : 'font-italic';

        return (
            <div className="input">
                <label htmlFor={name} className={labelClass}>{label}</label>
                <input
                    type={'checkbox'}
                    id={name}
                    name={name}
                    checked={value}
                    value={value ? 'true' : 'false'}
                    disabled={this.props.disabled}
                    onChange={this.updateValue.bind(this)}
                    className={`input-native${classes === null ? '' : ` ${classes}`}`}
                    onKeyPress={this.keyPressed.bind(this)}
                />
            </div>
        );
    }
}
