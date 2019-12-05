
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
        flex: 7,
        height: "100%",
        backgroundColor: "#78e3b6",
        paddingTop: 20
    },
    
    subPage:{
        display: "flex",
        flex: 93,
        height: "100%"
    }
  });

