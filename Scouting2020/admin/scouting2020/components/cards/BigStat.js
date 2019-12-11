// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View, Text
    
} from 'react-native';


var globalStyles = require('../../assets/styles/Global'); 
export class BigStat extends Component{

    constructor(props){
    
        super(props);
        
        this.state = {
            
        }

    }
    
    render(){
        return(
        <View style={[globalStyles.bigStat]}>
    <Text style={[globalStyles.bigStatText, globalStyles.h1]}>{this.props.stat}</Text>
    <Text style={[globalStyles.bigStatText, globalStyles.h3]}>{this.props.comment}</Text>

        </View>
        );
    }

}