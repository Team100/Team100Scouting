import React from 'react';
import {Component} from 'react';

import {View, Text, TextInput, Button} from 'react-native';

export class TeamSelect extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <View>
        <TextInput placeholder="Match" />
        <TextInput placeholder="Team" />
        <Button title="Prepare for Game Start" />
      </View>
    );
  }
}