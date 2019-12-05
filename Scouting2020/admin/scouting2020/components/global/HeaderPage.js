// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View, Text
    
} from 'react-native';

var globalStyles = require('../../assets/styles/Global'); 
export class HeaderPage extends Component{

    constructor(props){
    
        super(props);
        
        this.state = {
            
        }

    }
    
    render(){
    
        return(
            <View style={[globalStyles.superPage]}>
                <View style={[globalStyles.header,{backgroundColor: `${this.props.bkg}`}]}>
                    <Text>{this.props.title}</Text>
                </View>
                <View style={[globalStyles.subPage]}>
                    {this.props.children}
                </View>

                
            </View>

        );
    }

}