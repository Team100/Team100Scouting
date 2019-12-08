
var React = require('react-native');

var {
  StyleSheet,
} = React;

module.exports = styles = StyleSheet.create({

    superPage:{
        flexDirection: "column",
        width: "100%",
        height: "100%"
        
    },
    header:{
        display: "flex",
        flex: 5,
        height: "100%",
        backgroundColor: "#78e3b6",
        paddingTop: 20,
                
    },
    headerText:{
        fontSize: 28,
        color: "#FFFFFF",
        textAlign: "center"
    },

    innerPage:{
        width: "100%",
        height: "100%"
    },
    subPage:{
        display: "flex",
        flex: 95,
        height: "100%"
    },

    h1: {
        fontSize: 48
    },
    h2:{
        fontSize: 40
    },
    h3:{
        fontSize: 36
    },
    h4: {
        fontSize: 24
    }
  });

