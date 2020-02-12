// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View, Text

} from 'react-native';


var globalStyles = require('../../assets/styles/Global');
export class Message extends Component{

    constructor(props){

        super(props);

        this.state = {

        }

    }

    render(){
        return(
        <View>
            <Text>{this.props.subject}</Text>
            <Text>{this.props.body}</Text>


        </View>
        );
    }

}
