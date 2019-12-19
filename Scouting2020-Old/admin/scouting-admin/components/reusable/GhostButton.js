import React from 'react';
import {Component} from 'react';
import {TouchableOpacity, Text} from 'react-native';
var globalStyles = require('../../styles/GlobalStyles');


export class GhostButton extends Component{
    constructor(props){
        super (props);
    }
    render(){
    
        if(this.props.style=="primary"){
            //console.warn("In primary");
            return(
                <TouchableOpacity style={[globalStyles.ghostButtonWrapper]} onPress = {this.props.callback}><Text style={[globalStyles.ghostButton, globalStyles.colorPrimary]}>{this.props.content}</Text></TouchableOpacity>
            );
        }
        else if(this.props.style=="secondary"){
            //console.warn("In secondary");
            return(
                <TouchableOpacity style={[globalStyles.ghostButtonWrapper]} onPress = {this.props.callback}><Text style={[globalStyles.ghostButton, globalStyles.colorSecondary]}>{this.props.content}</Text></TouchableOpacity>
            );
        }
        else if(this.props.style=="info"){
            return(
                <TouchableOpacity style={[globalStyles.ghostButtonWrapper]} onPress = {this.props.callback}><Text style={[globalStyles.ghostButton, globalStyles.colorInfo]}>{this.props.content}</Text></TouchableOpacity>
            );
        }
        else if(this.props.style=="ok"){
            return(
                <TouchableOpacity style={[globalStyles.ghostButtonWrapper]} onPress = {this.props.callback}><Text style={[globalStyles.ghostButton, globalStyles.colorOk]}>{this.props.content}</Text></TouchableOpacity>
            );
        }
        else if(this.props.style=="warn"){
            return(
                <TouchableOpacity style={[globalStyles.ghostButtonWrapper]} onPress = {this.props.callback}><Text style={[globalStyles.ghostButton, globalStyles.colorWarn]}>{this.props.content}</Text></TouchableOpacity>
            );
        }
        else if(this.props.style=="error"){
            return(
                <TouchableOpacity style={[globalStyles.ghostButtonWrapper]} onPress = {this.props.callback}><Text style={[globalStyles.ghostButton, globalStyles.colorError]}>{this.props.content}</Text></TouchableOpacity>
            );
            
        }
        else{
            //console.warn("Skipped Styling of Button");
            return(
                <TouchableOpacity style={[globalStyles.ghostButtonWrapper]} onPress = {this.props.callback}><Text style={[globalStyles.ghostButton, globalStyles.colorPrimary]}>{this.props.content}</Text></TouchableOpacity>
            );
        }
        
        
    }
}