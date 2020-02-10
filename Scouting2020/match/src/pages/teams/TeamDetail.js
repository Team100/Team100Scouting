import React, {Component} from "react";
import BoldCard from "../../components/card/boldcard/BoldCard";
import PlainChip from "../../components/chip/plainchip/PlainChip";

export default class TeamDetail extends Component{
    render(){
        return(

            <div>
                <h1>{this.props.match.params.id}</h1>



            </div>
        );
    }
}
