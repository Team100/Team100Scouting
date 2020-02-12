// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View,
    Text

} from 'react-native';
import {
    ProgressChart
} from "react-native-chart-kit";
import Colors from '../../assets/styles/Colors';
import { ScreenInfo } from '../../shared/ScreenInfo';

var globalStyles = require('../../assets/styles/Global');


export class TeamScoringProgress extends Component{

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
        const data = {
            labels: ["POS", "ROT", "HIGH","LOW"], // optional
            data: [0.2, .4, 0.6, 0.8],
          };
          const chartConfig = {

                backgroundColor: '#FFFFFF',
                backgroundGradientFrom: '#FFFFFF',
                backgroundGradientTo: '#FFFFFF',
                color: (opacity = 1) => `rgba(0, 0, 0, ${opacity})`,
                style: {
                  borderRadius: 16
                }
          };
        // Return JSX that React Native will display
        return(
            <View>
                <ProgressChart data={data} width={ScreenInfo.getDeviceWidth()} height={220} chartConfig = {chartConfig}/>


            </View>

        );
    }

}
