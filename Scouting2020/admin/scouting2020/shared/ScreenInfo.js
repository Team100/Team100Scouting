import React from 'react';
import {Dimensions} from 'react-native';
export class ScreenInfo{
    static FORCE_TABLET = false;
    constructor(){

    }
    static getDeviceWidth(){
        return Dimensions.get("window").width;
    
    }
    static getDeviceHeight(){
        return Dimensions.get("window").height;
    }
    static isTabletLayout(){
        return this.getDeviceWidth() > this.getDeviceHeight() || this.getDeviceWidth() > 900 || this.FORCE_TABLET; 
    }


}