// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View,
    Text
    
} from 'react-native';
import { HeaderPage } from '../../components/global/HeaderPage';
import Colors from '../../assets/styles/Colors';

var globalStyles = require('../../assets/styles/Global'); 


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
                <HeaderPage title="Team 100 Scouting" bkg={Colors.HomeColor.color}>
                    <View>
                        <Text style={[globalStyles.h1]}>Hello Dean K.</Text>
                        <Text style={[globalStyles.h3]}>1990 Region Regional</Text>
                        <Text style={[globalStyles.h4]}>(1990test)</Text>
                    </View>
                    
                

                </HeaderPage>
                
                
            </View>

        );
    }

}