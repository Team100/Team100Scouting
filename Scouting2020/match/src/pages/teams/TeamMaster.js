import React, {Component} from "react";
import BoldCard from "../../components/card/boldcard/BoldCard";
import PlainChip from "../../components/chip/plainchip/PlainChip";
import Biglist from "../../components/lists/biglist/Biglist";

export default class TeamMaster extends Component{
    render(){
        return(

            <Biglist items={[{avatar: "person", name: "100", body: "Rank 1"},{avatar: "person", name: "199", body: "Rank 2"}]}/>


        );
    }
}
