'use strict';

var React = require('react-native');

var {
    StyleSheet,
} = React;


module.exports = StyleSheet.create({

    page:{
        
        width: "100%",
        height: "100%",
        backgroundColor: "#0539B7",
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
        flex: 7,
        marginTop: 25,
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
        backgroundColor: "#0539B7",
        color: "#FFFFFF"

    },


    innerPage:{
        flex: 93,
        backgroundColor:"#FFFFFF",
        overflow: "scroll"
        
    },
    innerPagePortrait:{
        borderTopLeftRadius: 30,
        borderTopRightRadius: 30,
        borderBottomLeftRadius: 30,
        borderBottomRightRadius: 30,
       

    },
    innerPageLandscape:{
        borderTopLeftRadius: 30,
        borderTopRightRadius: 0,
        borderBottomLeftRadius: 30,
        borderBottomRightRadius: 0,

    }




});