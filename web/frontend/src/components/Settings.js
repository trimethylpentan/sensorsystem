// @flow

import React from 'react';
import Button from "./Button";
import TextInput from "./Input/TextInput";
import type {SettingsType} from "../types/SettingsType";
import NumberInput from "./Input/NumberInput";
import CheckBox from "./Input/Checkbox";
import DataFormatter from "../helpers/DateFormatter";

type Props = {
    settings: SettingsType,
    accept: Function<SettingsType>,
};

class Settings extends React.Component<Props, SettingsType> {

    state = {
        host: this.props.settings.host,
        maxPoints: this.props.settings.maxPoints,
        random: this.props.settings.random,
        dateRange: {
            enabled: this.props.settings.dateRange.enabled,
            from: DataFormatter.format(this.props.settings.dateRange.from.toISOString()),
            to: DataFormatter.format(this.props.settings.dateRange.to.toISOString()),
        }
    };

    updateInput(event: SyntheticInputEvent) {
        const field = event.target.name;
        const newValue = event.target.value;

        this.setState({
            [field]: newValue,
        });
    }

    updateInputValue(name: string, newValue: any) {
        this.setState({
            [name]: newValue,
        });
    }

    updateDateRangeEnabled(name: string, newValue) {
        let newDateRange = {...this.state.dateRange};
        newDateRange[name] = newValue;

        this.setState({
            dateRange: newDateRange,
        });
    }

    updateDateRangeSetting(event) {
        const field = event.target.name;
        const newValue = event.target.value;

        let newDateRange = {...this.state.dateRange};
        newDateRange[field] = newValue;

        this.setState({
            dateRange: newDateRange,
        });
    }

    accept() {
        let newSettings = {...this.state};

        // Datumse validieren
        const from = new Date(DataFormatter.germanToIso(this.state.dateRange.from));
        if (isNaN(from.getTime())) {
            newSettings.dateRange.from = DataFormatter.format(new Date(new Date().setHours(0,0,0,0)));
        }

        const to = new Date(DataFormatter.germanToIso(this.state.dateRange.to));
        if (isNaN(to.getTime())) {
            newSettings.dateRange.to = DataFormatter.format(new Date(new Date().setHours(24,0,0,0)));
        }

        console.log({newSettings});
        this.props.accept(newSettings);
    }

    render() {
        const {settings: oldSettings} = this.props;
        const {host, maxPoints, random, dateRange} = this.state;
        const {enabled, from, to} = dateRange;
        // console.log('============================================');

        return (
            <div className="settings">
                <TextInput
                    name={'host'}
                    label={'API Host'}
                    value={host}
                    classes={oldSettings.host === host ? null : 'input-value-changed'}
                    update={this.updateInput.bind(this)}
                    onEnter={this.accept.bind(this)}
                />
                <NumberInput
                    name={'maxPoints'}
                    label={'Maximale Punkte'}
                    value={maxPoints}
                    min={1}
                    max={1000}
                    classes={oldSettings.maxPoints === maxPoints ? null : 'input-value-changed'}
                    update={this.updateInputValue.bind(this)}
                    onEnter={this.accept.bind(this)}
                />
                <CheckBox
                    name={'random'}
                    label={'Zufällige Punkte'}
                    value={random}
                    classes={oldSettings.random === random ? null : 'input-value-changed'}
                    disabled={enabled}
                    update={this.updateInputValue.bind(this)}
                    onEnter={this.accept.bind(this)}
                />
                <hr/>
                <CheckBox
                    name={'enabled'}
                    label={'Datumsbereich einschränken'}
                    value={enabled}
                    classes={oldSettings.dateRange.enabled === enabled ? null : 'input-value-changed'}
                    disabled={random}
                    update={this.updateDateRangeEnabled.bind(this)}
                    onEnter={this.accept.bind(this)}
                />
                {enabled && (
                    <>
                        <TextInput
                            name={'from'}
                            label={'Von'}
                            value={from}
                            classes={DataFormatter.format(oldSettings.dateRange.from.toISOString()) === from ? null : 'input-value-changed'}
                            update={this.updateDateRangeSetting.bind(this)}
                            onEnter={this.accept.bind(this)}
                        />
                        <TextInput
                            name={'to'}
                            label={'Bis'}
                            value={to}
                            classes={DataFormatter.format(oldSettings.dateRange.to.toISOString()) === to ? null : 'input-value-changed'}
                            update={this.updateDateRangeSetting.bind(this)}
                            onEnter={this.accept.bind(this)}
                        />
                    </>
                )}
                <br/>
                <br/>
                <Button name={'accept'} active={false} click={this.accept.bind(this)}>&Uuml;bernehmen</Button>
            </div>
        );
    }
}

export default Settings;
