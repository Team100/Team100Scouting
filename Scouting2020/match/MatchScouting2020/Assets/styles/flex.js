'use strict';

var React = require('react-native');

var {StyleSheet} = React;

module.exports = StyleSheet.create({
  flexCol: {
    display: 'flex',
    flexDirection: 'column',
  },
  flexRow: {
    display: 'flex',
    flexDirection: 'column',
  },
  smallFlex: {
    flex: 1,
  },
  bigFlex: {
    flex: 2,
  },
  hugeFlex: {
    flex: 3,
  },
});
