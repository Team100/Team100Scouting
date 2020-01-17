import React from 'react';
import {Component} from 'react';

import {View, Text, TextInput, Button} from 'react-native';


var flexStyles = require('../../../Assets/styles/flex');
export class Flex1 extends Component {
    constructor(props) {
        super(props);
        this.state = {};
    }

    render() {
        return (
            <View style={[flexStyles.flex2]}>
                {this.props.children}
            </View>
        );
    }
}
