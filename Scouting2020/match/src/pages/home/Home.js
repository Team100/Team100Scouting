import React, {Component} from "react";
import BoldCard from "../../components/card/boldcard/BoldCard";
import PlainChip from "../../components/chip/plainchip/PlainChip";

export default class Home extends Component{
    render(){
        return(

            <div>
                <br />
                <div>
                    <BoldCard>
                        <h4>Hello Alex K.</h4>
                        <h5>2020 San Francisco</h5>
                        <PlainChip text={"Qual Matches"}/>
                    </BoldCard>

                </div>



            </div>
        );
    }
}
