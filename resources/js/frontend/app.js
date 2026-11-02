/**
 * Before you edit these, read the documentation on how these files are compiled:
 * https://docs.ajv.net/developers/building-assets
 *
 * Edits here don't take place until you compile these assets and then upload them.
 */
import moment from "moment";
import popper from "popper.js";
import bootstrap from "bootstrap";
import jQuery from "jquery";

// Import the bids functionality
import { addBid, removeBid } from "./bids";
import handleExternalRedirects from "./external_redirect";

// import '../entrypoint';

// Import the mapping function
import {
  render_airspace_map,
  render_base_map,
  render_live_map,
  render_route_map,
} from "../maps/index";

window.ajv = window.ajv || {};
window.ajv.bids = {
  addBid,
  removeBid,
};

window.ajv.map = {
  render_airspace_map,
  render_base_map,
  render_live_map,
  render_route_map,
};

// window.bootstrap = bootstrap; // Disabled to prevent overwriting Bootstrap 5 CDN object
window.moment = moment;
window.popper = popper;
window.$ = jQuery;

// External redirects handler
handleExternalRedirects();
