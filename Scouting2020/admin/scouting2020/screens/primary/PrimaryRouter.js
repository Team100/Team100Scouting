import { Home } from "./Home";
import React from 'react';

import { createBottomTabNavigator } from 'react-navigation-tabs';
import { createMaterialBottomTabNavigator } from 'react-navigation-material-bottom-tabs';
import {Ionicons, MaterialCommunityIcons, SimpleLineIcons} from '@expo/vector-icons';
import { TeamSelect } from "./TeamSelect";
import {View} from "react-native";
import Colors from "../../assets/styles/Colors";
import { EventInfo } from "./EventInfo";
import { Picklist } from "./Picklist";



const PrimaryRouter = createMaterialBottomTabNavigator({
    Home: {
      screen: Home,
      navigationOptions:{
        tabBarIcon: ({tintColor}) => (
          <View>
            <Ionicons name = "md-apps" size={24} color={tintColor}/>
          </View>
        ),
        tabBarColor: Colors.HomeColor.color
        
      }
      
    },
    TeamSelect:{
      screen: TeamSelect,
      navigationOptions:{
        tabBarIcon: ({tintColor}) => (
          <View>
            <MaterialCommunityIcons name = "robot-industrial" size={24} color={tintColor}/>
          </View>
        ),
        tabBarColor: Colors.TeamSelectColor.color,
        tabBarLabel: "Teams"

      }
    },
    Picklist:{
      screen: Picklist,
      navigationOptions:{
        tabBarIcon: ({tintColor}) => (
          <View>
            <MaterialCommunityIcons name = "format-list-bulleted-type" size={24} color={tintColor}/>
          </View>
        ),
        tabBarColor: Colors.PicklistColor.color,
        tabBarLabel: "Picklist"

      }
    },
    EventInfo:{
      screen: EventInfo,
      navigationOptions:{
        tabBarIcon: ({tintColor}) => (
          <View>
            <Ionicons name = "ios-calendar" size={24} color={tintColor}/>
          </View>
        ),
        tabBarColor: Colors.EventInfoColor.color,
        tabBarLabel: "Event Info"

      }
    }
    
  },{
    initialRouteName: 'Home',
    activeColor: '#f0edf6',
    inactiveColor: '#3e2465',
    barStyle: { backgroundColor: '#694fad' },
    shifting: true

  

  });
  export default PrimaryRouter;