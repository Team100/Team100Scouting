import React from 'react';

import { createBottomTabNavigator } from 'react-navigation-tabs';
import { createMaterialBottomTabNavigator } from 'react-navigation-material-bottom-tabs';
import {Ionicons, MaterialCommunityIcons, SimpleLineIcons} from '@expo/vector-icons';
import {View} from "react-native";
import Colors from "../../assets/styles/Colors";
import TeamRouter from "./TeamProfile/TeamRouter";
import HomeRouter from "./Home/HomeRouter";
import PicklistRouter from "./Picklist/PicklistRouter";
import MatchUpRouter from "./MatchUp/MatchUpRouter";
import MoreRouter from "./More/MoreRouter";



const PrimaryRouter = createMaterialBottomTabNavigator({
    Home: {
      screen: HomeRouter,
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
      screen: TeamRouter,
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
      screen: PicklistRouter,
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
    MatchUp:{
      screen: MatchUpRouter,
      navigationOptions:{
        tabBarIcon: ({tintColor}) => (
          <View>
            <Ionicons name = "md-contrast" size={24} color={tintColor}/>
          </View>
        ),
        tabBarColor: Colors.EventInfoColor.color,
        tabBarLabel: "Match Up"

      }
    },
    More:{
      screen: MoreRouter,
      navigationOptions:{
        tabBarIcon: ({tintColor}) => (
          <View>
            <Ionicons name = "md-more" size={24} color={tintColor}/>
          </View>
        ),
        tabBarColor: Colors.MoreColor.color,
        tabBarLabel: "More"


      }
    }

  },{
    initialRouteName: 'Home',
    activeColor: Colors.MenuTextColor.color,
    inactiveColor: Colors.MenuTextColor.secondary,
    barStyle: { backgroundColor: '#694fad' },
    shifting: true



  });


  export default PrimaryRouter;
