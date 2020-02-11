import React from "react";
import { createStackNavigator } from "react-navigation-stack";
import {Picklist} from "./Picklist";




const PicklistRouter = createStackNavigator(
    {
        Picklist: {screen: Picklist}
    },
    {
        initialRouteName: 'Picklist',



    }
);
export default PicklistRouter;
