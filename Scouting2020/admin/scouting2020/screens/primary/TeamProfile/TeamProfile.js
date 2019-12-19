// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View,
    Text,
    ScrollView
    
} from 'react-native';
import { HeaderPage } from '../../../components/global/HeaderPage';
import Colors from '../../../assets/styles/Colors';
import { TeamAttributesProgress } from '../../../components/charts/TeamAttributesProgress';
import { Br } from '../../../components/Br';
import { TeamScoringProgress } from '../../../components/charts/TeamScoringProgress';
import { BigStat } from '../../../components/cards/BigStat';
import { LocationsPie } from '../../../components/charts/LocationsPie';
import { StartingLocationsPie } from '../../../components/charts/StartingLocationsPie';
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
                    <ScrollView style={[globalStyles.full]}>

                    <View>
                        <Text style={[globalStyles.h1]}>Team 100</Text>
                        <Text style={[globalStyles.h2]}>The Wildhats</Text>
                        
                    </View>
                    <Br />
                    <View>
                    <BigStat stat="#3" comment="Current TBA Rank" />
                    <Br />
                    <BigStat stat="66%" comment="Pick Fit" />
                

                    </View>
                    

                    <Br />
                    
                                        
                    <View>
                        <Text style={[globalStyles.h2]}>Team Strengths</Text>
                        <Text style={[globalStyles.h3]}>Competition</Text>
                        <TeamAttributesProgress />
                        <Br />
                        <Text style={[globalStyles.h3]}>Scoring</Text>
                        <TeamScoringProgress />

                    </View>
                    <Br />
                    <Br />
                    <View style={[globalStyles.pageCenter]}>
                        <Text style={[globalStyles.h2]}>Cycle Insights</Text>
                        <BigStat stat="6" comment="avg. cycles/match" />
                        <Br />
                        <BigStat stat="75%" comment="acquisition likelihood" />
                    </View>
                    <Br />
                    <Br />
                    <View>
                        <Text style={[globalStyles.h2]}>Field Locations</Text>
                        <LocationsPie />
                        <StartingLocationsPie />
                    </View>

                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <Br />
                    <View><Text>End of File</Text></View>
                    </ScrollView>
                  
                    
                

                </HeaderPage>
                
                
            </View>

        );
    }

}