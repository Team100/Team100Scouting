// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View,
    Text
    
} from 'react-native';
import { HeaderPage } from '../../../components/global/HeaderPage';
import Colors from '../../../assets/styles/Colors';
import { TeamAttributesProgress } from '../../../components/charts/TeamAttributesProgress';
import { Br } from '../../../components/Br';
var globalStyles = require('../../../assets/styles/Global'); 


export class TeamProfile extends Component{

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
                <HeaderPage title="Team 100" bkg={Colors.TeamSelectColor.color}>
                    <View>
                        <Text style={[globalStyles.h1]}>Team 100</Text>
                        <Text style={[globalStyles.h2]}>The Wildhats</Text>
                        
                    </View>
                    <Br />
                    <Br />
                                        
                    <View>
                        <Text style={[globalStyles.h2]}>Team Strengths</Text>
                        <Text style={[globalStyles.h3]}>Competition</Text>
                        <TeamAttributesProgress />
                        <Br />
                        <Text style={[globalStyles.h3]}>Scoring</Text>
                    </View>
                    
                

                </HeaderPage>
                
                
            </View>

        );
    }

}