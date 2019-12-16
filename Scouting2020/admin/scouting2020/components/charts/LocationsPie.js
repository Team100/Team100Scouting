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
              name: "Rocket 1",
              amnt: 5,
              color: Colors.ChartColor.colorA,
              legendFontColor: Colors.ChartColor.colorA,
              legendFontSize: 15
            },
            {
              name: "Rocket 2",
              amnt: 2,
              color: Colors.ChartColor.colorB,
              legendFontColor: Colors.ChartColor.colorB,
              legendFontSize: 15
            },
            {
              name: "Rocket 3",
              amnt: 3,
              color: Colors.ChartColor.colorC,
              legendFontColor: Colors.ChartColor.colorC,
              legendFontSize: 15
            },
            {
              name: "Ship Front",
              amnt: 3,
              color: Colors.ChartColor.colorD,
              legendFontColor: Colors.ChartColor.colorD,
              legendFontSize: 15
            },
            {
              name: "Ship Side",
              amnt: 3,
              color: Colors.ChartColor.colorE,
              legendFontColor: Colors.ChartColor.colorE,
              legendFontSize: 15
            },
            
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