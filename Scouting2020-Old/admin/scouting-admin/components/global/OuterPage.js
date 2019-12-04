// Import the required libraries
import React from 'react';
import {Component} from 'react';
import {PageLayout} from '../../shared/PageLayout';

import {
    View,
    
} from 'react-native';

//var globalStyles = require('../../assets/styles'); //We don't have styles
var globalStyles = require('../../styles/GlobalStyles');


export class OuterPage extends Component{

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
        if(PageLayout.isTabletLayout()){
            return(
            <View style={[globalStyles.outerPage, globalStyles.outerPageLandscape]}>
                    {this.props.children}
                    
                </View>
            );

        }else{
            return(
                <View style={[globalStyles.outerPage, globalStyles.outerPagePortrait]}>
                    {this.props.children}
                    
                </View>
    
            );

        }
        
    }

}