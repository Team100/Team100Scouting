// Import the required libraries
import React from "react";
import { Component } from "react";
import { Header } from "react-native-elements";

import { View, Text, ImageBackground } from "react-native";
import { NavigationBar, Title } from '@shoutem/ui'


var globalStyles = require("../../assets/styles/Global");
export class HeaderPage extends Component {
  constructor(props) {
    super(props);

    this.state = {};
  }

  render() {
    /*return(
            <View>
              <View>
              <NavigationBar
  centerComponent={<Title>TITLE</Title>}
/>
</View>
<View style={[globalStyles.innerPage]}>
  {this.props.children}
</View>
            </View>

        );*/
    /*
    return (
      <View>
       <ImageBackground source={require("../../assets/images/adrien-converse-kCrrUx7US04-unsplash.jpg")}
  style={{ width: "100%", height: 70 }}
>
  <NavigationBar
    styleName="clear"
    centerComponent={<Title>{this.props.title}</Title>}
  />
</ImageBackground>

        <View style={[globalStyles.innerPage]}>{this.props.children}</View>
      </View>

     */
    return(
        <View style={[globalStyles.innerPage]}>
            {this.props.children}
        </View>
    );
  }
}
