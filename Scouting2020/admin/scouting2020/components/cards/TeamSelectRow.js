// Import the required libraries
import React from 'react';
import {Component} from 'react';

import {
    View, Text
    
} from 'react-native';

import {ListItem} from 'react-native-elements';

var globalStyles = require('../../assets/styles/Global'); 
export class TeamSelectRow extends Component{

    constructor(props){
    
        super(props);
        
        this.state = {
            
        }

    }
    
    render(){
        return(
        <ListItem
              leftAvatar={{ source: { uri: "https://beaver-app-assets.oss-us-west-1.aliyuncs.com/assets/eggchat/ui/Artboard%201.png?x-oss-process=style/thumbnail-tiny" } }}
              title={`${this.props.teamInfo.team}`}
              subtitle={`${this.props.teamInfo.name} \nRANK: ${this.props.teamInfo.rank}`}
              onPress = {()=>{
                this.props.navigation.navigate("TeamProfile");
              }

              }
            />
        );
    }

}