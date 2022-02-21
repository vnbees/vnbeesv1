import React, { useEffect } from "react";
// import {publicApiFunction} from "@app/utility";
export default function Root(props) {
  useEffect(()=>{
    // const result = publicApiFunction("hello utility workflow");
    console.log("call");
  },[])
  return <section>{props.name} is mounted!</section>;
}
