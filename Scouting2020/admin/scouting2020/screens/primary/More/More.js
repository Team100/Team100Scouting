// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View,
    Text

} from 'react-native';
import { HeaderPage } from '../../../components/global/HeaderPage';
import Colors from '../../../assets/styles/Colors';

var globalStyles = require('../../../assets/styles/Global');

// Create a new class called ComponentTemplate
// ComponentTemplate should be renamed to your class name
export class More extends Component{
    static navigationOptions = {
        title: "More",
    }

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
                <HeaderPage title="More Options" bkg={Colors.MoreColor.color}>
                    <Text style={[globalStyles.h1]}>Your Account</Text>
                </HeaderPage>


            </View>

        );
    }

}
