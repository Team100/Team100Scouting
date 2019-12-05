// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View,
    Text
    
} from 'react-native';
import { HeaderPage } from '../../components/global/HeaderPage';
import Colors from '../../assets/styles/Colors';

//var globalStyles = require('../../assets/styles'); //We don't have styles

// Create a new class called ComponentTemplate
// ComponentTemplate should be renamed to your class name
export class Home extends Component{

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
            <View> 
                <HeaderPage title="hi" bkg={Colors.HomeColor.color}>
                    <Text>Hello</Text>
                </HeaderPage>
                
                
            </View>

        );
    }

}