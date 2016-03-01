<?php 
/**

 * Adds hyperlinks for certain legal citations to string passed into it
 * Written by Joe Morris joe@morris.cloud 2/29/2016
 * License: AGPL3 https://www.gnu.org/licenses/agpl.txt
 */

function legal_hyperlinkify ( $input ) {
  // remove spaces
  $retval = preg_replace('/\s+/', ' ', $input);
  // remove tags, especially MS Word spans, that can break up what would otherwise be links
  $retval = strip_tags ( $retval, '<a><div><i><b>' );
  // USC (S, A)
  $retval = preg_replace ( '/(\d+)[\s]+U\.S\.C\.S?A?\.?[\s]+[§]+[\s]+(\d+)/u', '<a class="citationlink" target="legal_reference" href="https://www.law.cornell.edu/uscode/text/$1/$2">$1 U.S.C. § $2</a>', $retval );
  // Code of Federal Regulations to LII
$retval = preg_replace ( '/(\d+)[\s]+C.F.R.[\s]+[§]+[\s]*(\d+)(.)?([\d]*)/u', '<a target="legal_reference" class="citationlink" href="https://www.law.cornell.edu/cfr/text/$1/$2$3$4">$1 C.F.R. § $2$3$4</a>', $retval );  
 // California codes -- tested for labor, civil, gov't, mil and vet. To offical CA code 
$retval = preg_replace('/(?:Cal.\s*)?(Unemp. Ins|[A-Z][a-z][a-z]\’?\'?t?|Military and Veterans)\.?[\s+]Code[,\s]*§+\s?(\d+)([0-9a-zA-B\.\(\)]*)/u', ' <a class="citationlink"  target="legal_reference" href="http://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml?lawCode=$1&sectionNum=$2">Cal. $1. Code § $2$3</a>', $retval);

  // Handles incorrect but common "Code Civ. Proc." (normally "Code" goes at end)
  $retval = preg_replace('/(?:Cal.\s*)?Code Civ\.?[\s+]Proc\.?,?§+\s+(\d+)([0-9a-zA-B\.\(\)]*)/u', ' <a class="citationlink"  target="legal_reference" href="http://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml?lawCode=CCP&sectionNum=$1">Cal. Civ. Proc. Code § $2$3</a>', $retval);
  $cal_code_fixme = array("lawCode=Civ", "lawCode=Lab", "lawCode=Gov’t", "lawCode=Gov't", "lawCode=Military and Veterans", "lawCode=Unemp. Ins");
  $cal_code_fixed  = array("lawCode=CIV", "lawCode=LAB", "lawCode=GOV", "lawCode=GOV", "lawCode=MVC", "lawCode=UIC");
  $retval = str_replace ($cal_code_fixme, $cal_code_fixed, $retval);
  // Cal Code Regs to official version hosted by westlaw. Link is to search results because direct linking doesn't work
  $retval = preg_replace("/Cal.[\s]+Code[\s]+o?f?\s?Regs.[\s]+tit\.?\s+(\d+),?\s+§\s+([\d\.]+)/u", "<a  class=\"citationlink\"  target=\"legal_reference\" href=\"https://govt.westlaw.com/calregs/Search/Results?t_T1=$1&t_T2=$2&t_S1=CA%20ADC%20s&Template=Find\">Cal. Code Regs. tit. $1 § $2</a>", $retval);
  // F.2/3d, F.Supp, other case citations go to Google Scholar again search results because no directly linking scheme seems to exist
  $retval = preg_replace("/(\d+)\s(F.[23]d|F.\s?Supp\.\s?2?d?\.?|Cal.\s?App.\s?3d|Cal.\s?App.\s?4th|S\.\s?Ct\.?|Cal\.\s?3d|Cal\.\s?4th|U\.S\.|P\.[2-3]d)\s?(\d+)/u", "<a  class=\"citationlink\" target=\"legal_reference\" href=\"https://scholar.google.com/scholar?q=$1+$2+$3&btnG=&hl=en&as_sdt=2006\">$1 $2 $3</a>", $retval);

  return $retval;
}
?>
