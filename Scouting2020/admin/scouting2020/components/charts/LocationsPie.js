// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View,
    Text
    
} from 'react-native';
import {
    ProgressChart, PieChart 
} from "react-native-chart-kit";
import { data, contributionData, pieChartData, progressChartData } from 'react-native-chart-kit/data';

import Colors from '../../assets/styles/Colors';
import { ScreenInfo } from '../../shared/ScreenInfo';

var globalStyles = require('../../assets/styles/Global'); 


export class LocationsPie extends Component{

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
        const data = [
            {
              name: "L Rocket 1",
              amnt: 5,
              color: "rgba(131, 167, 234, 1)",
              legendFontColor: "#7F7F7F",
              legendFontSize: 15
            },
            {
              name: "L Rocket 2",
              amnt: 2,
              color: "#F00",
              legendFontColor: "#7F7F7F",
              legendFontSize: 15
            },
            {
              name: "L Rocket 3",
              amnt: 3,
              color: "#FF0",
              legendFontColor: "#7F7F7F",
              legendFontSize: 15
            },
            {
              name: "R Rocket 1",
              amnt: 3,
              color: "#000",
              legendFontColor: "#7F7F7F",
              legendFontSize: 15
            },
            {
              name: "R Rocket 2",
              amnt: 1,
              color: "rgb(0, 0, 255)",
              legendFontColor: "#7F7F7F",
              legendFontSize: 15
            },
            {
              name: "R Rocket 3",
              amnt: 2,
              color: "rgb(0, 255, 255)",
              legendFontColor: "#7F7F7F",
              legendFontSize: 15
            }
          ];
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
<PieChart
  data={data}
  width={ScreenInfo.getDeviceWidth()}
  height={220}
  chartConfig={chartConfig}
  accessor="amnt"
  backgroundColor="transparent"
  paddingLeft="15"
  absolute
/>                
                
            </View>

        );
    }

}