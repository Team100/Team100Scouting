// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View,
    Text
    
} from 'react-native';

var globalStyles = require('../assets/styles/Global'); 


export class Br extends Component{

    // Allow the object to be created with properties
    constructor(props){
    
        // React Native will handle properties with this line
        super(props);
        
        // Set any state variables 
        this.state = {
            
        }

    }
    
    // Render any visual aspects of the component
    render(){
    
        // Return JSX that React Native will display
        return(
            <View style={[globalStyles.br]}> 
                
                
            </View>

        );
    }

}