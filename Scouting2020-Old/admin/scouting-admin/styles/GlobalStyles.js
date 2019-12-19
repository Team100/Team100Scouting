'use strict';

var React = require('react-native');

var {
    StyleSheet,
} = React;

var bkgColor = "#0539B7";

var primaryColor = "#0539B7";
var secondaryColor = "#3F79E8";
var infoColor = "#B056E8";
var okColor = "#4AFF9D";
var warnColor = "#E8DC56";
var errColor = "#FF956B";


module.exports = StyleSheet.create({

    page:{
        
        width: "100%",
        height: "100%",
        backgroundColor: bkgColor,
    },

    pagePortrait:{
        flexDirection: "column",
    },
    pageLandscape:{
        flexDirection:"column",
    },

    headerWrapper: {
        width: "100%",
        height: "100%",
        flex: 5,
        marginTop: 10,
        
    },
    headerText:{
        fontSize: 36,
        color: "#FFFFFF"
    },
    outerPage:{
        width: "100%",
        height: "100%",
        flex: 90
    },
    outerPagePortrait:{
        flexDirection: "column",
    },
    outerPageLandscape:{
        flexDirection:"row-reverse",
    },
    


    menu: {
        width: "100%",
        height: "100%",
        flex: 7,
        backgroundColor: bkgColor,
        color: "#FFFFFF",
        display: "flex"

    },
    menuPortrait:{
        flexDirection:"row"
    },
    menuLandscape:{
        flexDirection:"column"
    },


    innerPage:{
        flex: 93,
        backgroundColor:"#FFFFFF",
        overflow: "scroll",
        borderColor: bkgColor,
        borderWidth: 5
        
    },
    
    innerPagePortrait:{
        borderTopLeftRadius: 30,
        borderTopRightRadius: 30,
        borderBottomLeftRadius: 30,
        borderBottomRightRadius: 30,
       

    },
    innerPageLandscape:{
        borderTopLeftRadius: 30,
        borderTopRightRadius: 30,//here
        borderBottomLeftRadius: 30,
        borderBottomRightRadius: 30,//here

    },

    colorPrimary:{
        color: primaryColor
    },
    colorSecondary:{
        color: secondaryColor
    },
    colorInfo:{
        color: infoColor
    },
    colorOk:{
        color: okColor
    },
    colorWarn:{
        color: warnColor
    },
    colorError:{
        color: errColor
    },



    /// Elements

    largeTextBox:{
        margin: 30,
        color: primaryColor,
        fontSize: 36
    },

    ghostButtonWrapper:{
        width: "100%",
    },
    ghostButton:{
        width: "100%",
        height: "100%",
        textAlign: "center",
        textAlignVertical: "center",
        fontSize: 28
    },
   



});