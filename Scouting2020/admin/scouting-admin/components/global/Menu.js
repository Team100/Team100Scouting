// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View,
    Text
} from 'react-native';

var globalStyles = require('../../styles/GlobalStyles');

// Create a new class called ComponentTemplate
// ComponentTemplate should be renamed to your class name
export class Menu extends Component{

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
            <View style={globalStyles.menu}>
                {this.props.children}
                
            </View>

        );
    }

}